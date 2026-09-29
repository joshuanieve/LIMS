using LIMS_API.Entities.RequestForm;

namespace LIMS_API.Repositories.RequestForm
{
    public interface IRequestFormRepo
    {
        Task<int> CreateRequestForm(CreateRequestForm request);
    }
}