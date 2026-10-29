using LIMS_API.Entities.Results;

namespace LIMS_API.Entities.Results
{
    public class GetResultsTable
    {
        public int RequestId { get; set; }
        public string? CustomerName { get; set; }
        public int? DivisionSection { get; set; }
        public DateTime? TargetDate { get; set; }
        public string? SampleType { get; set; }
        public int? Status { get; set; }
        public bool? Arta { get; set; }
        public List<GetRFEListDetails> Samples { get; set; } = new();
    }

    public class GetRFEListDetails
    {
        public int TestId { get; set; }
        public string? LaboratoryNumber { get; set; }
        public string? Analysis { get; set; }
        public string? AnalysisOthers { get; set; }
        public List<GetRFELabWorkDetails> LabWorks { get; set; } = new();
    }

    public class GetRFELabWorkDetails
    {
        public int LabWorkId { get; set; }
    }
}
