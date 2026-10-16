using LIMS_API.Entities.Results;
using LIMS_API.Repositories.Results;

namespace LIMS_API.Services.Results
{
    public class ResultsService : IResultsService
    {
        private readonly IResultsRepo _ResultsRepository;

        public ResultsService(IResultsRepo ResultforEncodingRepository)
        {
        _ResultsRepository = ResultforEncodingRepository;
        }

        public async Task<List<GetResultsTable>> GetResultsDetails()
        {
            return await _ResultsRepository.GetResultsDetails();
        }
    }
}
