using System;
using System.Collections.Generic;

namespace LIMS_API.Models;

public partial class LabRequestLabWork
{
    public int LabWorkId { get; set; }

    public int TestId { get; set; }

    public int Analysis { get; set; }

    public string? AnalysisSpecify { get; set; }

    public string? Unit { get; set; }

    public string? Result { get; set; }

    public int? AssignedTo { get; set; }

    public int? AssignedBy { get; set; }

    public DateTime? AssignedAt { get; set; }

    public DateTime? StartedAt { get; set; }

    public DateTime? CompletedAt { get; set; }

    public string? Remarks { get; set; }

    public DateTime? CreatedAt { get; set; }

    public DateTime? UpdatedAt { get; set; }

    public virtual LabAnalysisList AnalysisNavigation { get; set; } = null!;

    public virtual LabRequestList Test { get; set; } = null!;
}
