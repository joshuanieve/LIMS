using LIMS_API.Entities.RequestForm;

namespace LIMS_API.Entities.ResultforEncoding
{
    public class GetRFEDetails
    {
        public int RequestId { get; set; }
        public DateTime? TargetDate { get; set; }
        public int? Status { get; set; }
        public DateTime? CreatedAt { get; set; }
        public DateTime? UpdatedAt { get; set; }
        public List<GetRFEListDetails> Samples { get; set; } = new();
    }

    public class GetRFEListDetails
    {
        public int TestId { get; set; }
        public int? RequestId { get; set; }
        public string? LaboratoryNumber { get; set; }
        public string? Sample { get; set; }
        public int? Status { get; set; }
        public DateTime? CreatedAt { get; set; }
        public DateTime? UpdatedAt { get; set; }
        public List<GetRFELabWorkDetails> LabWorks { get; set; } = new();
    }

    public class GetRFELabWorkDetails
    {
        public int LabWorkId { get; set; }
        public int TestId { get; set; }
        public int? Analysis { get; set; }
        public string? Unit { get; set; }
        public string? Result { get; set; }
        public int? AssignedTo { get; set; }
        public DateTime? CreatedAt { get; set; }
        public DateTime? UpdatedAt { get; set; }
    }
}
