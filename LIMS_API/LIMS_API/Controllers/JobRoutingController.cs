
using LIMS_API.Services.JobRouting;
using Microsoft.AspNetCore.Mvc;

namespace LIMS_API.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class JobRoutingController : ControllerBase{
        private readonly IJobRoutingService _JobRoutingService;

        public JobRoutingController(IJobRoutingService jobRoutingService)
        {
            _JobRoutingService = jobRoutingService;
        }

        [HttpGet]
        public async Task<IActionResult> GetJobRoutingTableData()
        {
            var result = await _JobRoutingService.GetJobRoutingTableData();
            return Ok(result);
        }
    }
}
