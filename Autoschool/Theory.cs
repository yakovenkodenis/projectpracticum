
namespace Autoschool
{
    class Theory
    {
        public string TheoryId { get; set; }
        public string TeacherId { get; set; }
        public string GroupId { get; set; }
        public string Room { get; set; }
        public string StartTime { get; set; }
        public string EndTime { get; set; }

        public override string ToString()
        {
            return "Id: " + TheoryId + "\tTeacherId: " + TeacherId + "\tGroupId: " + GroupId + "\tRoom: " + Room +
                   "\tStart: " + StartTime + "\tEnd: " + EndTime;
        }
    }
}
