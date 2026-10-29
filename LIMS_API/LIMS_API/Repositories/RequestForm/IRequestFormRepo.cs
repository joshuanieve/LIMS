using LIMS_API.Entities.RequestForm;
using LIMS_API.Entities.RequestPending;

namespace LIMS_API.Repositories.RequestForm
{
    public interface IRequestFormRepo
    {
        Task<int> CreateRequestForm(CreateRequestForm request);

    }
}