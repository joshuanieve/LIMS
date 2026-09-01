using System;
using System.Collections.Generic;

namespace LIMS_API.Models;

public partial class LabRequestList
{
    public int TestId { get; set; }

    public int? RequestId { get; set; }

    public string? LaboratoryNumber { get; set; }

    public string? Sample { get; set; }

    public string? CustomerSampleCode { get; set; }

    public DateTime? DateTimeCollected { get; set; }

    public string? PlaceCollected { get; set; }

    public string? Analysis { get; set; }
}
