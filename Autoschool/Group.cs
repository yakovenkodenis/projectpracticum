
namespace Autoschool
{
    class Group
    {
        public string GroupId { get; set; }
        public string AutoschoolId { get; set; }
        public string Name { get; set; }
        public string PracticeStart { get; set; }
        public string PracticeDays { get; set; }
        public string PracticeTeacher { get; set; }
        public string PracticeMeetpoint { get; set; }
        public string PracticeReservCount { get; set; }

        public override string ToString()
        {
            return "Id: " + GroupId + "\tAutoschool: " + AutoschoolId + "\tName: " + Name + "\tPracticeStart: " +
                   PracticeStart + "\tPracticeDays: " + PracticeDays + "\tPracticeTeacher: " + PracticeTeacher +
                   "\tMeetpoint: " + PracticeMeetpoint + "\tReservCount: " + PracticeReservCount;
        }
    }
}
