using LIMS_API.Entities.JobRouting;
using LIMS_API.Repositories.JobRouting;

namespace LIMS_API.Services.JobRouting
{
    public class JobRoutingService : IJobRoutingService
    {
        private readonly IJobRoutingRepo _JobRoutingRepository;

        public JobRoutingService(IJobRoutingRepo JobRoutingRepository){
            _JobRoutingRepository = JobRoutingRepository;
        }

        public async Task<List<GetJobRoutingDetails>> GetJobRoutingTableData() { 
            return await _JobRoutingRepository.GetJobRoutingTableData();
        }
    }
}
