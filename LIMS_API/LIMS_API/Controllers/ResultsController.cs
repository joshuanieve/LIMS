using LIMS_API.Services.Results;
using Microsoft.AspNetCore.Mvc;

namespace LIMS_API.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class ResultsController : ControllerBase
    {
        private readonly IResultsService _ResultsService;

        public ResultsController(IResultsService ResultsService)
        {
            _ResultsService = ResultsService;
        }

        [HttpGet]
        public async Task<IActionResult> GetResultforEncodingTableData()
        {
            var result = await _ResultsService.GetResultsDetails();
            return Ok(result);
        }
    }
}

