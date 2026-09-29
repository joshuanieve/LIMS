using LIMS_API.Entities.RequestForm;
using LIMS_API.Repositories.RequestForm;

namespace LIMS_API.Services.RequestForm
{
    public class RequestFormService : IRequestFormService
    {
        private readonly IRequestFormRepo _requestFormRepo;


        public RequestFormService(IRequestFormRepo requestFormRepo){
            _requestFormRepo = requestFormRepo;
        }


        public async Task<int> CreateRequestForm(CreateRequestForm request){
            if (request.Samples == null || request.Samples.Count == 0){
                throw new Exception("At least one sample is required.");
            }

            foreach (var sample in request.Samples){
                if (string.IsNullOrWhiteSpace(sample.Testarray)){
                    throw new Exception("Every sample must have at least one analysis.");
                }
            }

            return await _requestFormRepo.CreateRequestForm(request);
        }

    }
}