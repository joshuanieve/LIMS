using LIMS_API.Entities.RequestPending;
using Microsoft.AspNetCore.Mvc;

namespace LIMS_API.Repositories.RequestPending
{
    public interface IRequestPendingRepo
    {
        Task<List<GetTable>> GetTable();

        Task<GetRequestDetails?> GetRequestPendingById(int id);

        Task<AcceptResult> AcceptRequest(int requestId, int userId, string userName);
    }
}
