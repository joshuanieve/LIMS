using LIMS_API.Entities.RequestPending;
using LIMS_API.Repositories.RequestPending;

namespace LIMS_API.Services.RequestPending
{
    public class RequestPendingService : IRequestPendingService
    {
        private readonly IRequestPendingRepo _requestPendingRepository;

        public RequestPendingService(IRequestPendingRepo requestPendingRepository){
            _requestPendingRepository = requestPendingRepository;
        }

        // GET ALL REQUESTS FOR TABLE
        public async Task<List<GetRequestPendingList>> GetRequestPendingList(){
            return await _requestPendingRepository.GetRequestPendingList();
        }

        // GET ONE REQUEST FOR VIEWING
        public async Task<GetRequestDetails?> GetRequestPendingById(int id){
            return await _requestPendingRepository.GetRequestPendingById(id);
        }
    }
}