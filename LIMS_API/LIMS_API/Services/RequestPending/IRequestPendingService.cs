using LIMS_API.Entities.RequestPending;

namespace LIMS_API.Services.RequestPending
{
    public interface IRequestPendingService
    {
        Task<List<GetTable>> GetTable();
        Task<GetRequestDetails?> GetRequestPendingById(int id);

        Task<AcceptResult> AcceptRequest(int requestId, int userId, string userName);
    }
}
