namespace LIMS_API.Entities.SystemAuditLogs
{
    public class GetAuditLog
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
        public List<GetAuditLogChange> Changes { get; set; } = new();
    }

    public class GetAuditLogChange
    {
        public string FieldName { get; set; } = null!;
        public string? OldValue { get; set; }
        public string? NewValue { get; set; }
    }
}