using LIMS_API.Entities.JobRouting;
using LIMS_API.Entities.ResultforEncoding;
using LIMS_API.Models;
using Microsoft.EntityFrameworkCore;

namespace LIMS_API.Repositories.ResultforEncoding
{
    public class ResultforEncodingRepo : IResultforEncodingRepo
    {
        private readonly LimsContext _context;

        public ResultforEncodingRepo(LimsContext context)
        {
            _context = context;
        }

        public async Task<List<GetRFEDetails>> GetResultforEncodingTableData()
        {
            return await _context.LabRequests
                .AsNoTracking()
                .Select(x => new GetRFEDetails
                {
                    RequestId = x.RequestId,
                    TargetDate = x.TargetDate,
                    Status = x.Status,

                    Samples = _context.LabRequestLists
                        .Where(s => s.RequestId == x.RequestId)
                        .Select(s => new GetRFEListDetails
                        {
                            TestId = s.TestId,
                            RequestId = s.RequestId,
                            LaboratoryNumber = s.LaboratoryNumber,
                            Sample = s.Sample,
                            Status = s.Status,

                            LabWorks = _context.LabRequestLabWorks
                                .Where(a => a.TestId == s.TestId)
                                .Select(a => new GetRFELabWorkDetails
                                {
                                    LabWorkId = a.LabWorkId,
                                    TestId = a.TestId,
                                    Analysis = a.Analysis,
                                    Unit = a.Unit,
                                    Result = a.Result,
                                    UserId = a.UserId,

                                })
                                .ToList()

                        })
                        .ToList()

                })
                .ToListAsync();   // changed
        }
    }
}
