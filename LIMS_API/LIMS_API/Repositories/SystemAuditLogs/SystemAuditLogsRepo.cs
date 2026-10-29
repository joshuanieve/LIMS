using LIMS_API.Entities.SystemAuditLogs;
using LIMS_API.Models;
using Microsoft.EntityFrameworkCore;

namespace LIMS_API.Repositories.SystemAuditLogs
{
    public class SystemAuditLogsRepo : ISystemAuditLogsRepo
    {
        private readonly LimsContext _context;

        public SystemAuditLogsRepo(LimsContext context)
        {
            _context = context;
        }

        public async Task<List<GetAuditLog>> GetSystemAuditLogs(int page = 1, int pageSize = 25)
        {
            return await _context.SysAuditLogs
                .AsNoTracking()
                .OrderByDescending(x => x.CreatedAt)
                .ThenByDescending(x => x.LogId)
                .Skip((page - 1) * pageSize)
                .Take(pageSize)
                .Select(x => new GetAuditLog
                {
                    LogId = x.LogId,
                    RequestId = x.RequestId,
                    TestId = x.TestId,
                    LabNumber = x.LabNumber,
                    LabWorkId = x.LabWorkId,
                    UserId = x.UserId,
                    UserName = x.UserName,
                    Action = x.Action,
                    Module = x.Module,
                    CreatedAt = x.CreatedAt,

                    Changes = _context.SysAuditLogsChanges
                        .Where(d => d.LogId == x.LogId)
                        .Select(d => new GetAuditLogChange
                        {
                            FieldName = d.FieldName ?? "",
                            OldValue = d.OldValue,
                            NewValue = d.NewValue
                        })
                        .ToList()
                })
                .ToListAsync();
        }
    }
}
