using LIMS_API.Entities.AnalysisList;
using Microsoft.AspNetCore.Mvc;

namespace LIMS_API.Repositories.AnalysisList
{
    public interface IAnalysisListRepo
    {
        Task<List<GetAnalysisList>> GetAnalysisList();
    }
}
