using LIMS_API.Entities.Results;

namespace LIMS_API.Services.Results
{
    public interface IResultsService
    {
        Task<List<GetResultsTable>> GetResultsDetails();
    }
}
