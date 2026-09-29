using LIMS_API.Entities.JobRouting;
using LIMS_API.Services.JobRouting;
using Microsoft.AspNetCore.Mvc;

namespace LIMS_API.Controllers
{
    public class JobRoutingController : ControllerBase{
        private readonly IJobRoutingService _JobRoutingService;
    }
}
