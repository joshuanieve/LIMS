using LIMS_API.Entities.Results;

namespace LIMS_API.Repositories.Results
{
    public interface IResultsRepo
    {
        Task<List<GetResultsTable>> GetResultsDetails();
    }
}
