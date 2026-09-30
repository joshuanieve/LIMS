namespace LIMS_API.Entities.JobRouting
{
    public class GetJobRoutingLists
    {
        public int TestId { get; set; }
        public int? RequestId { get; set; }
        public string? LaboratoryNumber { get; set; }
        public string? Sample { get; set; }
        public DateTime? DateTimeCollected { get; set; }
        public string? PlaceCollected { get; set; }
        public string? Analysis { get; set; }
        public int? Status { get; set; }
        public DateTime? CreatedAt { get; set; }
        public DateTime? UpdatedAt { get; set; }
    }
}
