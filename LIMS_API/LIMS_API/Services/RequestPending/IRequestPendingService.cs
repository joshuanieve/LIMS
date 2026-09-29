using LIMS_API.Entities.RequestPending;

namespace LIMS_API.Services.RequestPending
{
    public interface IRequestPendingService
    {
        Task<List<GetRequestPendingList>> GetRequestPendingList();
        Task<GetRequestDetails?> GetRequestPendingById(int id);
    }
}
