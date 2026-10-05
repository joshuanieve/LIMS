using LIMS_API.Entities.ResultforEncoding;

namespace LIMS_API.Repositories.ResultforEncoding
{
    public interface IResultforEncodingRepo
    {
        Task<List<GetRFEDetails>> GetResultforEncodingTableData();
    }
}
