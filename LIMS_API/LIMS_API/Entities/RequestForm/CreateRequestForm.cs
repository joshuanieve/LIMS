namespace LIMS_API.Entities.RequestForm
{
    public class CreateRequestForm
    {
        public int? CustomerId { get; set; }
        public string? CustomerName { get; set; }
        public string? EmailAddress { get; set; }
        public int? DivisionSection { get; set; }
        public DateTime? DateSubmitted { get; set; }
        public string? LabAna { get; set; }
        public DateTime? DateTimeRelease { get; set; }
        public string? SamType { get; set; }
        public string? SamTypeSpecify { get; set; }
        public string? SampRet { get; set; }
        public bool? Subinfo1 { get; set; }
        public bool? Subinfo2 { get; set; }
        public bool? Subinfo3 { get; set; }
        public bool? Subinfo4 { get; set; }
        public string? Subinfo2Specify { get; set; }
        public string? Subinfo4Specify { get; set; }
        public string? Instruction { get; set; }
        public int? Status { get; set; }
        public DateTime? CreatedAt { get; set; }
        public DateTime? UpdatedAt { get; set; }
        public List<CreateRequestSample> Samples { get; set; } = new();

    }

    public class CreateRequestSample
    {
        public int RequestId { get; set; }
        public string? LaboratoryNumber { get; set; }
        public string? Sample { get; set; }
        public string? CustomerSampleCode { get; set; }
        public DateTime? DateTimeCollected { get; set; }
        public string? PlaceCollected { get; set; }
        public string? PlaceCode { get; set; }
        public string? Analysis { get; set; }
        public string? AnalysisOthers { get; set; }
        public int? Status { get; set; }
        public DateTime? CreatedAt { get; set; }
        public DateTime? UpdatedAt { get; set; }

    }
}