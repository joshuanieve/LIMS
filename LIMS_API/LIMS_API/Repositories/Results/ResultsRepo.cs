using LIMS_API.Entities.Results;
using LIMS_API.Models;
using Microsoft.EntityFrameworkCore;

namespace LIMS_API.Repositories.Results
{
    public class ResultsRepo : IResultsRepo
    {
        private readonly LimsContext _context;

        public ResultsRepo(LimsContext context)
        {
            _context = context;
        }

        public async Task<List<GetResultsTable>> GetResultsDetails()
        {
            return await _context.LabRequests
                .AsNoTracking()
                .Select(x => new GetResultsTable
                {
                    RequestId = x.RequestId,
                    CustomerName = x.CustomerName,
                    DivisionSection = x.DivisionSection,
                    TargetDate = x.TargetDate,
                    SampleType = x.SampleType,
                    Status = x.Status,
                    Arta = x.Arta,

                    Samples = _context.LabRequestLists
                        .Where(s => s.RequestId == x.RequestId)
                        .Select(s => new GetRFEListDetails
                        {
                            TestId = s.TestId,
                            LaboratoryNumber = s.LaboratoryNumber,
                            Analysis = s.Analysis,

                            LabWorks = _context.LabRequestLabWorks
                                .Where(a => a.TestId == s.TestId)
                                .Select(a => new GetRFELabWorkDetails
                                {
                                    LabWorkId = a.LabWorkId

                                })
                                .ToList()

                        })
                        .ToList()

                })
                .ToListAsync();   // changed
        }
    }
}
