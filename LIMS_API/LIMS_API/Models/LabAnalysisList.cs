using System;
using System.Collections.Generic;

namespace LIMS_API.Models;

public partial class LabAnalysisList
{
    public int AnalysisId { get; set; }

    public string? Type { get; set; }

    public string? Category { get; set; }

    public string? Analyte { get; set; }

    public string? Method { get; set; }
}
