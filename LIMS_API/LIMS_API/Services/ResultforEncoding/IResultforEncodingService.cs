using LIMS_API.Entities.ResultforEncoding;

namespace LIMS_API.Services.ResultforEncoding
{
    public interface IResultforEncodingService
    {
        Task<List<GetRFEDetails>> GetResultforEncodingTableData();
    }
}
