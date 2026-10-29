using System;
using System.Collections.Generic;

namespace LIMS_API.Models;

public partial class SysAuditLog
{
    public int LogId { get; set; }

    public int? RequestId { get; set; }

    public int? TestId { get; set; }

    public string? LabNumber { get; set; }

    public int? LabWorkId { get; set; }

    public int? UserId { get; set; }

    public string? UserName { get; set; }

    public string Action { get; set; } = null!;

    public string? Module { get; set; }

    public DateTime CreatedAt { get; set; }

    public virtual ICollection<SysAuditLogsChange> SysAuditLogsChanges { get; set; } = new List<SysAuditLogsChange>();
}
