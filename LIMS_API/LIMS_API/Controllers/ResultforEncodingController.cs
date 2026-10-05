using LIMS_API.Services.ResultforEncoding;
using Microsoft.AspNetCore.Mvc;

namespace LIMS_API.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class ResultforEncodingController : ControllerBase
    {
        private readonly IResultforEncodingService _ResultforEncodingService;

        public ResultforEncodingController(IResultforEncodingService RFEService)
        {
            _ResultforEncodingService = RFEService;
        }

        [HttpGet]
        public async Task<IActionResult> GetResultforEncodingTableData()
        {
            var result = await _ResultforEncodingService.GetResultforEncodingTableData();
            return Ok(result);
        }
    }
}
