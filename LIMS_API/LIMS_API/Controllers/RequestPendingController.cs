
using LIMS_API.Entities.RequestPending;
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
        public async Task<IActionResult> GetTable(){
            var result = await _RPService.GetTable();
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


        [HttpPost("{id}/accept")] // ACCEPT A PENDING REQUEST
        public async Task<IActionResult> AcceptRequest(int id, [FromBody] AcceptRequestDto dto)
        {
            var result = await _RPService.AcceptRequest(id, dto.UserId, dto.UserName);

            return result switch
            {
                AcceptResult.NotFound => NotFound(new { message = "Request not found." }),
                AcceptResult.AlreadyAccepted => Conflict(new { message = "This request has already been accepted." }),
                _ => Ok(new { message = "Request accepted." })
            };
        }
    }
}