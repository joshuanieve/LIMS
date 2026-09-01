using LIMS_API.Entities.AnalysisList;
using LIMS_API.Repositories.AnalysisList;

namespace LIMS_API.Services.AnalysisList
{
    public class AnalysisListService : IAnalysisListService
    {
        private readonly IAnalysisListRepo _analysisListRepository;

        public AnalysisListService(IAnalysisListRepo analysisListRepository)
        {
            _analysisListRepository = analysisListRepository;
        }

        public async Task<List<GetAnalysisList>> GetAnalysisList()
        {
            return await _analysisListRepository.GetAnalysisList();
        }
    }
}