using LIMS_API.Entities.SystemAuditLogs;

namespace LIMS_API.Repositories.SystemAuditLogs
{
    public interface ISystemAuditLogsRepo
    {
        Task<List<GetAuditLog>> GetSystemAuditLogs(int page = 1, int pageSize = 25);
    }
}
