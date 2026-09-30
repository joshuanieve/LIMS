using LIMS_API.Entities.JobRouting;

namespace LIMS_API.Services.JobRouting
{
    public interface IJobRoutingService
    {
        Task<List<GetJobRoutingDetails>> GetJobRoutingTableData();
    }
}
