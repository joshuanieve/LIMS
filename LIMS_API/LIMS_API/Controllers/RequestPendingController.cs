
using LIMS_API.Services.RequestPending;
using Microsoft.AspNetCore.Mvc;



namespace LIMS_API.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class RequestPendingController : ControllerBase
    {
        private readonly IRequestPendingService _RPService;

        public RequestPendingController(IRequestPendingService requestPendingService)
        {
            _RPService = requestPendingService;
        }

        [HttpGet] // GET THE TABLE DATA
        public async Task<IActionResult> GetAnalysisList(){
            var result = await _RPService.GetRequestPendingList();
            return Ok(result);
        }

        [HttpGet("{id}")] // GET DATA FOR VIEWING
        public async Task<IActionResult> GetRequestPendingById(int id)
        {
            var result = await _RPService.GetRequestPendingById(id);
            if (result == null)
            {
                return NotFound(new
                {
                    message = "Request not found."
                });
            }

            return Ok(result);
        }
    }
}