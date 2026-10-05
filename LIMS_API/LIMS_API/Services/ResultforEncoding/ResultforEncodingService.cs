using LIMS_API.Entities.ResultforEncoding;
using LIMS_API.Repositories.ResultforEncoding;

namespace LIMS_API.Services.ResultforEncoding
{
    public class ResultforEncodingService : IResultforEncodingService
    {
        private readonly IResultforEncodingRepo _RFERepository;

        public ResultforEncodingService(IResultforEncodingRepo ResultforEncodingRepository)
        {
            _RFERepository = ResultforEncodingRepository;
        }

        public async Task<List<GetRFEDetails>> GetResultforEncodingTableData()
        {
            return await _RFERepository.GetResultforEncodingTableData();
        }
    }
}
