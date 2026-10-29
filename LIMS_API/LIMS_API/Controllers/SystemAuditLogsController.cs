using LIMS_API.Services.SystemAuditLogs;
using Microsoft.AspNetCore.Mvc;

namespace LIMS_API.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class SystemAuditLogsController : ControllerBase
    {
        private readonly ISystemAuditLogsService _LogsService;

        public SystemAuditLogsController(ISystemAuditLogsService LogsService)
        {
            _LogsService = LogsService;
        }

        [HttpGet]
        public async Task<IActionResult> GetSystemAuditLogs()
        {
            var result = await _LogsService.GetSystemAuditLogs();
            return Ok(result);
        }
    }
}


