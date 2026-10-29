namespace LIMS_API.Constants
{
    public static class RequestStatus
    {
        public const int Pending = 1;
        public const int ForRouting = 2;
        public const int AcceptedByAnalyst = 3;
        public const int Encoding = 4;
        public const int SectionChiefReview = 5;
        public const int DivisionChiefReview = 6;
        public const int Released = 7;
    }
}