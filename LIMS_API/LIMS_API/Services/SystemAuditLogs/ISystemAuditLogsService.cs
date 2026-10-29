using LIMS_API.Entities.SystemAuditLogs;

namespace LIMS_API.Services.SystemAuditLogs
{
    public interface ISystemAuditLogsService
    {
        Task<List<GetAuditLog>> GetSystemAuditLogs();
    }
}
