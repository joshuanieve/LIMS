using System;
using System.Collections.Generic;

namespace LIMS_API.Models;

public partial class SysAuditLogsChange
{
    public int ChangeId { get; set; }

    public int LogId { get; set; }

    public string? FieldName { get; set; }

    public string? OldValue { get; set; }

    public string? NewValue { get; set; }

    public virtual SysAuditLog Log { get; set; } = null!;
}
