using LIMS_API.Services.AnalysisList;
using Microsoft.AspNetCore.Mvc;

namespace LIMS_API.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class AnalysisListController : ControllerBase
    {
        private readonly IAnalysisListService _analysisListService;

        public AnalysisListController(IAnalysisListService analysisListService)
        {
            _analysisListService = analysisListService;
        }

        [HttpGet]
        public async Task<IActionResult> GetAnalysisList()
        {
            var result = await _analysisListService.GetAnalysisList();

            return Ok(result);
        }
    }
}