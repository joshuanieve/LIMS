using LIMS_API.Entities.RequestForm;

namespace LIMS_API.Services.RequestForm
{
    public interface IRequestFormService
    {
        Task<int> CreateRequestForm(CreateRequestForm request);
    }
}