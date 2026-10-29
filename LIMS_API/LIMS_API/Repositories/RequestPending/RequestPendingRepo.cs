using LIMS_API.Entities.RequestPending;
using LIMS_API.Entities.SystemAuditLogs;
using LIMS_API.Models;
using LIMS_API.Constants;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

namespace LIMS_API.Repositories.RequestPending
{
    public class RequestPendingRepo : IRequestPendingRepo
    {
        private readonly LimsContext _context;

        public RequestPendingRepo(LimsContext context)
        {
            _context = context;
        }

        // LIST FOR REQUEST SAMPLE MODULE TABLE
        public async Task<List<GetTable>> GetTable()
        {
            return await _context.LabRequests
                .AsNoTracking()
                .Where(x => x.Status == RequestStatus.Pending
                         && x.ReceivedBy == null)
                .OrderBy(x => x.RequestId)
                .Select(x => new GetTable
                {
                    RequestId = x.RequestId,
                    CustomerName = x.CustomerName,
                    DivisionSection = x.DivisionSection,
                    DateSubmitted = x.DateSubmitted,
                    TargetDate = x.TargetDate,
                    LabAnalysis = x.LabAnalysis
                })
                .ToListAsync();
        }

        public async Task<GetRequestDetails?> GetRequestPendingById(int id)
        {
            return await _context.LabRequests
                .AsNoTracking()
                .Where(x => x.RequestId == id)
                .Select(x => new GetRequestDetails
                {
                    RequestId = x.RequestId,
                    CustomerName = x.CustomerName,
                    EmailAddress = x.EmailAddress,
                    DivisionSection = x.DivisionSection,
                    DateTime = x.DateSubmitted,
                    LabAnalysis = x.LabAnalysis,
                    DateTimeRelease = x.TargetDate,
                    SampleType = x.SampleType,
                    SampleRetrieval = x.SampleRetrieval,
                    SubInfo1 = x.SubInfo1,
                    SubInfo2 = x.SubInfo2,
                    SubInfo3 = x.SubInfo3,
                    SubInfo4 = x.SubInfo4,
                    Instruction = x.Instruction,

                    Samples = _context.LabRequestLists
                        .Where(s => s.RequestId == x.RequestId)
                        .Select(s => new GetRequestSampleDetails
                        {
                            TestId = s.TestId,
                            LaboratoryNumber = s.LaboratoryNumber,
                            Sample = s.Sample,
                            CustomerSampleCode = s.CustomerSampleCode,
                            DateTimeCollected = s.DateTimeCollected,
                            PlaceCollected = s.PlaceCollected,
                            Analysis = s.Analysis,
                            AnalysisOthers = s.AnalysisOthers
                        })
                        .ToList()
                })
                .FirstOrDefaultAsync();
        }

        public async Task<AcceptResult> AcceptRequest(int requestId, int userId, string userName)
        {
            var request = await _context.LabRequests.FindAsync(requestId);

            if (request == null) return AcceptResult.NotFound;
            if (request.Status != RequestStatus.Pending) return AcceptResult.AlreadyAccepted;

            var now = DateTime.Now;

            request.Status = RequestStatus.ForRouting;              // received
            request.ReceivedBy = userId;
            request.ReceivedAt = now;
            request.UpdatedAt = now;

            // audit log
            var log = new SysAuditLog
            {
                RequestId = requestId,
                UserId = userId,
                UserName = userName,
                Action = "Received request",
                Module = "Request Pending",
                CreatedAt = now
            };
            log.SysAuditLogsChanges.Add(new SysAuditLogsChange
            {
                FieldName = "Status",
                OldValue = "Submitted",
                NewValue = "Received"
            });
            _context.SysAuditLogs.Add(log);

            await _context.SaveChangesAsync();

            return AcceptResult.Success;
        }
    }
}
