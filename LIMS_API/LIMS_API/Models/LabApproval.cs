using System;
using System.Collections.Generic;

namespace LIMS_API.Models;

public partial class LabApproval
{
    public int ApprovalId { get; set; }

    public int? RequestId { get; set; }

    public int? TestId { get; set; }

    public int? UserId { get; set; }

    public string? Type { get; set; }

    public DateTime? SignedAt { get; set; }

    public DateTime? CreatedAt { get; set; }

    public DateTime? UpdatedAt { get; set; }
}
