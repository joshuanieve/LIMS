namespace LIMS_API.Entities.RequestPending
{
    public class GetRequestDetails
    {
        public int RequestId { get; set; }
        public string? CustomerName { get; set; }
        public string? EmailAddress { get; set; }
        public int? DivisionSection { get; set; }
        public DateTime? DateTime { get; set; }
        public string? LabAnalysis { get; set; }
        public DateTime? DateTimeRelease { get; set; }
        public string? SampleType { get; set; }
        public string? SampleRetrieval { get; set; }
        public bool? SubInfo1 { get; set; }
        public string? SubInfo2 { get; set; }
        public bool? SubInfo3 { get; set; }
        public string? SubInfo4 { get; set; }
        public string? Instruction { get; set; }
        public DateTime? CreatedAt { get; set; }
        public DateTime? UpdatedAt { get; set; }
        public List<GetRequestSampleDetails> Samples { get; set; } = [];
    }
}
