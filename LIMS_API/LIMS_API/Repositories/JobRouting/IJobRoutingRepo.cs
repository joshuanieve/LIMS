using LIMS_API.Entities.JobRouting;

namespace LIMS_API.Repositories.JobRouting
{
    public interface IJobRoutingRepo
    {
        Task<List<GetJobRoutingDetails>> GetJobRoutingTableData();
    }
}
