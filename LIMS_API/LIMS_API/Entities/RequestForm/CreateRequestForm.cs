namespace LIMS_API.Entities.RequestForm
{
    public class CreateRequestForm
    {
        public string? CustomerName { get; set; }

        public string? EmailAddress { get; set; }

        public int? DivisionSection { get; set; }

        public string? LabAna { get; set; }

        public DateTime? DateTimeRelease { get; set; }

        public string? SamType { get; set; }

        public string? SamTypeSpecify { get; set; }

        public string? SampRet { get; set; }

        public bool? Subinfo1 { get; set; }

        public bool? Subinfo2 { get; set; }

        public string? Subinfo2Specify { get; set; }

        public bool? Subinfo3 { get; set; }

        public bool? Subinfo4 { get; set; }

        public string? Subinfo4Specify { get; set; }

        public string? Instruction { get; set; }

        public DateTime? CreatedAt { get; set; }

        public DateTime? UpdatedAt { get; set; }

        public List<CreateRequestSample> Samples { get; set; } = new();
    }

    public class CreateRequestSample
    {
        public string? LaboratoryNumber { get; set; }

        public string? Sample { get; set; }

        public string? CustomerSampleCode { get; set; }

        public DateTime? DateCollected { get; set; }

        public string? PlaceCollected { get; set; }

        public string? Testarray { get; set; }

        public DateTime? CreatedAt { get; set; }

        public DateTime? UpdatedAt { get; set; }
    }
}