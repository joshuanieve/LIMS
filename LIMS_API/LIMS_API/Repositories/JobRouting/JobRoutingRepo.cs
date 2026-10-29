using LIMS_API.Entities.JobRouting;
using LIMS_API.Models;
using Microsoft.EntityFrameworkCore;

namespace LIMS_API.Repositories.JobRouting
{
    public class JobRoutingRepo : IJobRoutingRepo
    {
        private readonly LimsContext _context;

        public JobRoutingRepo(LimsContext context)
        {
            _context = context;
        }

        public async Task<List<GetJobRoutingDetails>> GetJobRoutingTableData()   // changed
        {
            return await _context.LabRequests
                .AsNoTracking()
                .Select(x => new GetJobRoutingDetails
                {
                    RequestId = x.RequestId,
                    CustomerName = x.CustomerName,
                    EmailAddress = x.EmailAddress,
                    DivisionSection = x.DivisionSection,
                    DateSubmitted = x.DateSubmitted,
                    LabAnalysis = x.LabAnalysis,
                    DateTimeRelease = x.TargetDate,

                    Samples = _context.LabRequestLists
                        .Where(s => s.RequestId == x.RequestId)
                        .Select(s => new GetJobRoutingLists
                        {
                            TestId = s.TestId,
                            LaboratoryNumber = s.LaboratoryNumber,
                            Sample = s.Sample,
                            DateTimeCollected = s.DateTimeCollected,
                            PlaceCollected = s.PlaceCollected,
                            Analysis = s.Analysis,
                            AnalysisOthers = s.AnalysisOthers
                        })
                        .ToList()
                })
                .ToListAsync();   // changed
        }
    }
}
