using System;
using System.Collections.Generic;

namespace LIMS_API.Models;

public partial class LabCounter
{
    public int Id { get; set; }

    public int? Year { get; set; }

    public int? Count { get; set; }
}
