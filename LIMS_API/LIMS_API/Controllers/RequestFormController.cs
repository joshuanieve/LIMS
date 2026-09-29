using LIMS_API.Entities.RequestForm;
using LIMS_API.Services.RequestForm;
using Microsoft.AspNetCore.Mvc;

namespace LIMS_API.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class RequestFormController : ControllerBase
    {
        private readonly IRequestFormService _requestFormService;

        public RequestFormController(
            IRequestFormService requestFormService){
                _requestFormService = requestFormService;
            }

        [HttpPost]
        public async Task<IActionResult> CreateRequestForm(
            [FromBody] CreateRequestForm request){
            try{
                var requestId =
                    await _requestFormService
                        .CreateRequestForm(request);

                return Ok(new{
                    message =
                        "Laboratory request created successfully.",
                    requestId = requestId
                });
            }
            catch (Exception ex){
                return BadRequest(new{
                    message = ex.Message
                });
            }
        }
    }
}