using LIMS_API.Entities.SystemAuditLogs;
using LIMS_API.Repositories.SystemAuditLogs;

namespace LIMS_API.Services.SystemAuditLogs
{
    public class SystemAuditLogsService : ISystemAuditLogsService
    {
        private readonly ISystemAuditLogsRepo _SystemAuditLogsRepository;

        public SystemAuditLogsService(ISystemAuditLogsRepo AuditLogsStatusRepository)
        {
            _SystemAuditLogsRepository = AuditLogsStatusRepository;
        }

        public async Task<List<GetAuditLog>> GetSystemAuditLogs()
        {
            return await _SystemAuditLogsRepository.GetSystemAuditLogs();
        }
    }
}
