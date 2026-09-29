using LIMS_API.Entities.RequestPending;
using LIMS_API.Models;
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
        public async Task<List<GetRequestPendingList>> GetRequestPendingList()
        {
            return await _context.LabRequests
                .AsNoTracking()
                .OrderBy(x => x.RequestId)
                .Select(x => new GetRequestPendingList
                {
                    RequestId = x.RequestId,
                    CustomerName = x.CustomerName,
                    EmailAddress = x.EmailAddress,
                    DivisionSection = x.DivisionSection,
                    DateTime = x.DateTime,
                    LabAnalysis = x.LabAnalysis,
                    TargetDate = x.TargetDate,
                    SampleType = x.SampleType,
                    SampleRetrieval = x.SampleRetrieval,
                    SubInfo1 = x.SubInfo1,
                    SubInfo2 = x.SubInfo2,
                    SubInfo3 = x.SubInfo3,
                    SubInfo4 = x.SubInfo4,
                    Instruction = x.Instruction,

                    CreatedAt = DateTime.Now,
                    UpdatedAt = DateTime.Now
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
                    DateTime = x.DateTime,
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
                            Analysis = s.Analysis
                        })
                        .ToList()
                })
                .FirstOrDefaultAsync();
        }


    }
}
