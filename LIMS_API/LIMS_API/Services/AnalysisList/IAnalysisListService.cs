using LIMS_API.Entities.AnalysisList;

namespace LIMS_API.Services.AnalysisList
{
    public interface IAnalysisListService
    {
        Task<List<GetAnalysisList>> GetAnalysisList();
    }
}