using System;
using System.Collections.Generic;

namespace LIMS_API.Models;

public partial class LabRequestLabWork
{
    public int LabWorkId { get; set; }

    public int TestId { get; set; }

    public int Analysis { get; set; }

    public string? Unit { get; set; }

    public string? Result { get; set; }

    public int? UserId { get; set; }

    public DateTime? CreatedAt { get; set; }

    public DateTime? UpdatedAt { get; set; }
}
