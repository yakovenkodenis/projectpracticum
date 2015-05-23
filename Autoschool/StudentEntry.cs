
namespace Autoschool
{
    class StudentEntry
    {
        public string EntryId { get; set; }
        public string StudentId { get; set; }
        public string SchoolId { get; set; }
        public string EntryTime { get; set; }
        public string AdditionalInfo { get; set; }

        public override string ToString()
        {
            return "Id: " + EntryId + "\tStudId: " + StudentId + "\tSchoolId: " + SchoolId + "\tEntryTime: " + EntryTime +
                   "\tAdditInfo: " + AdditionalInfo;
        }
    }
}
