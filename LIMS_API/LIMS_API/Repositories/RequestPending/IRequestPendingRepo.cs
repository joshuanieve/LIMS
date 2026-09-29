using LIMS_API.Entities.RequestPending;
using Microsoft.AspNetCore.Mvc;

namespace LIMS_API.Repositories.RequestPending
{
    public interface IRequestPendingRepo
    {
        Task<List<GetRequestPendingList>> GetRequestPendingList();

        Task<GetRequestDetails?> GetRequestPendingById(int id);
    }
}
